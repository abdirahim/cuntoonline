# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env

# Run migrations
php artisan migrate

# Create storage link
php artisan storage:link

# Start development server
php artisan serve
