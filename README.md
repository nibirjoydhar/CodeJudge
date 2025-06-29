# CodeJudge

A modern online judge system I built to help developers practice coding problems and run programming contests. It's powered by Laravel and Judge0, making it both powerful and easy to use.

## Features

- Solve programming problems across different difficulty levels
- Get instant feedback on your code submissions
- Create and manage programming contests
- Track your progress with detailed analytics
- Join discussions and learn from others

## Tech Stack

- Laravel 10.x + PHP 8.1
- Blade + Livewire + Tailwind CSS
- MySQL 8.0
- Judge0 CE for code execution
- Docker for containerization

## Quick Start

```bash
# Clone the repo
git clone https://github.com/nibirjoydhar/CodeJudge
cd CodeJudge

# Install dependencies
composer install
npm install && npm run build

# Set up environment
cp .env.example .env
php artisan key:generate

# Start services
docker-compose up -d
php artisan migrate:fresh --seed
php artisan serve
```

## Configuration

Set up your `.env` file with these essentials:
```env
APP_NAME=CodeJudge
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=codejudge
DB_USERNAME=your_username
DB_PASSWORD=your_password

JUDGE0_API_URL=http://localhost:2358
JUDGE0_API_KEY=your_api_key
```

## Security

I've implemented several security measures:
- Isolated Docker containers for code execution
- Resource limits to prevent abuse
- Standard web security practices
- Rate limiting for fair usage

## Contributing

Found a bug or have a feature idea? Pull requests are welcome! Just:
1. Fork the repo
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Open a PR

## License

MIT License - feel free to use this code however you want, just give me credit where it's due.

## Contact

- Email: [nibirjoydhar@gmail.com](mailto:nibirjoydhar@gmail.com)
- LinkedIn: [linkedin.com/in/nibirjoydhar](https://linkedin.com/in/nibirjoydhar)
- GitHub: [github.com/nibirjoydhar](https://github.com/nibirjoydhar)
- Portfolio: [nibirjoydhar.github.io/he-portfolio](https://nibirjoydhar.github.io/he)

---

Developed by Nibir Joydhar
