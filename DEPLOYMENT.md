# 🚀 Deployment Guide

This guide provides detailed instructions for deploying the Task Management System to a production environment.

## Option 1: Docker Deployment (Recommended)

The easiest way to deploy the application is using Docker and Docker Compose.

### Prerequisites
- Server with Docker and Docker Compose installed.
- Git installed.

### Steps

1.  **Clone the Repository**
    ```bash
    git clone https://github.com/yourusername/task-management.git
    cd task-management
    ```

2.  **Environment Configuration**
    Copy the example environment file and configure it for production:
    ```bash
    cp .env.example .env
    ```
    
    Edit `.env` and update the following:
    - `APP_ENV=production`
    - `APP_DEBUG=false`
    - `APP_URL=https://your-domain.com`
    - `DB_CONNECTION=mysql` (or sqlite if preferred for small scale)
    - Database credentials (if using MySQL)
    - Mail server settings

3.  **Build and Run Containers**
    ```bash
    docker-compose up -d --build
    ```

4.  **Post-Deployment Setup**
    Run these commands inside the container to set up the application:
    ```bash
    # Install dependencies (if not fully baked in image)
    docker-compose exec app composer install --optimize-autoloader --no-dev
    
    # Generate App Key
    docker-compose exec app php artisan key:generate
    
    # Run Migrations and Seeders
    docker-compose exec app php artisan migrate --seed --force
    
    # Link Storage
    docker-compose exec app php artisan storage:link
    
    # Optimize Cache
    docker-compose exec app php artisan config:cache
    docker-compose exec app php artisan route:cache
    docker-compose exec app php artisan view:cache
    ```

5.  **Nginx Configuration (Proxy)**
    Typically, you will run a reverse proxy (like Nginx or Traefik) on the host machine to forward traffic to port 8000 (or whatever port you exposed in docker-compose).

---

## Option 2: Manual VPS Deployment (Ubuntu/Nginx)

### Prerequisites
- Ubuntu 22.04 LTS
- PHP 8.1 or 8.2
- Nginx
- MySQL or SQLite
- Composer
- Node.js & NPM
- Supervisor (for queues)

### Steps

1.  **Install Dependencies**
    ```bash
    sudo apt update
    sudo apt install -y nginx php8.2-fpm php8.2-cli php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip unzip git supervisor
    ```

2.  **Setup Database**
    ```bash
    sudo mysql -u root -p
    CREATE DATABASE task_management;
    CREATE USER 'task_user'@'localhost' IDENTIFIED BY 'your_secure_password';
    GRANT ALL PRIVILEGES ON task_management.* TO 'task_user'@'localhost';
    EXIT;
    ```

3.  **Clone and Setup App**
    ```bash
    cd /var/www
    sudo git clone https://github.com/yourusername/task-management.git
    sudo chown -R www-data:www-data task-management
    cd task-management
    
    # Install PHP dependencies
    sudo -u www-data composer install --optimize-autoloader --no-dev
    
    # Setup .env
    sudo -u www-data cp .env.example .env
    sudo nano .env # Update DB credentials and APP_URL
    
    # Generate Key
    sudo -u www-data php artisan key:generate
    
    # Run Migrations
    sudo -u www-data php artisan migrate --seed --force
    
    # Link Storage
    sudo -u www-data php artisan storage:link
    
    # Build Frontend
    npm install
    npm run build
    ```

4.  **Configure Nginx**
    Create `/etc/nginx/sites-available/task-management`:
    ```nginx
    server {
        listen 80;
        server_name your-domain.com;
        root /var/www/task-management/public;
    
        add_header X-Frame-Options "SAMEORIGIN";
        add_header X-Content-Type-Options "nosniff";
    
        index index.php;
    
        charset utf-8;
    
        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }
    
        location = /favicon.ico { access_log off; log_not_found off; }
        location = /robots.txt  { access_log off; log_not_found off; }
    
        error_page 404 /index.php;
    
        location ~ \.php$ {
            fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
            fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
            include fastcgi_params;
        }
    
        location ~ /\.(?!well-known).* {
            deny all;
        }
    }
    ```
    Enable site:
    ```bash
    sudo ln -s /etc/nginx/sites-available/task-management /etc/nginx/sites-enabled/
    sudo nginx -t
    sudo systemctl reload nginx
    ```

5.  **Configure Supervisor (Worker)**
    Create `/etc/supervisor/conf.d/task-worker.conf`:
    ```ini
    [program:task-worker]
    process_name=%(program_name)s_%(process_num)02d
    command=php /var/www/task-management/artisan queue:work --sleep=3 --tries=3 --max-time=3600
    autostart=true
    autorestart=true
    stopasgroup=true
    killasgroup=true
    user=www-data
    numprocs=2
    redirect_stderr=true
    stdout_logfile=/var/www/task-management/storage/logs/worker.log
    stopwaitsecs=3600
    ```
    Start supervisor:
    ```bash
    sudo supervisorctl reread
    sudo supervisorctl update
    sudo supervisorctl start task-worker:*
    ```

## 🔒 Security Checklist

- [ ] Ensure `APP_DEBUG` is set to `false`.
- [ ] Set a strong `APP_KEY`.
- [ ] Configure SSL/TLS (use Certbot/Let's Encrypt).
- [ ] Ensure file permissions are correct (storage/cache directories writable by web server).
- [ ] Restrict database access to localhost only.
