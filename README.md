# CodeJudge

CodeJudge is a robust online judge system that allows users to solve programming problems and submit their solutions for automated evaluation.

## Features

- **Problem Management**
  - Create and manage programming problems
  - Support for multiple difficulty levels (easy/medium/hard)
  - Detailed problem descriptions with input/output formats and constraints
  - Sample test cases for better understanding

- **Code Submission**
  - Support for multiple programming languages (C++, Python, Java)
  - Real-time submission status updates
  - Detailed error reporting and feedback
  - View submission history

- **Judge System**
  - Local Judge0 CE instance via Docker
  - Secure code execution environment
  - Multiple test case validation
  - Performance metrics tracking

- **User Interface**
  - Clean and intuitive problem display
  - Syntax-highlighted code editor
  - Mobile-responsive design
  - Discussion system for each problem

## Technical Stack

- **Backend**: Laravel PHP Framework
- **Frontend**: Blade Templates with Tailwind CSS
- **Database**: PostgreSQL
- **Cache**: Redis
- **Judge System**: Judge0 CE (Community Edition)
- **Containerization**: Docker & Docker Compose

## Prerequisites

- Docker and Docker Compose
- PHP >= 8.0
- Composer
- Node.js and npm

## Installation

1. Clone the repository:
```bash
git clone [repository-url]
cd CodeJudge
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install and compile frontend assets:
```bash
npm install
npm run dev
```

4. Set up environment variables:
```bash
cp .env.example .env
php artisan key:generate
```

5. Start the Judge0 CE services:
```bash
docker-compose up -d
```

6. Run database migrations:
```bash
php artisan migrate
```

## Configuration

### Judge0 Configuration

The system supports both RapidAPI's Judge0 service and local Judge0 CE instance. The local instance is configured to run on:
- API Endpoint: http://localhost:2358
- Worker Count: 5
- Maximum Queue Size: 100

### Database Configuration

The system uses PostgreSQL for both the main application and Judge0 CE. Configure the following in your `.env` file:
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=codejudge
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## Usage

1. Create a new problem through the admin interface
2. Add test cases (both sample and hidden)
3. Users can view problems and submit solutions
4. System automatically judges submissions against all test cases
5. Users can view their submission history and discuss problems

## Security

- All user code is executed in isolated Docker containers
- Rate limiting on submissions
- Input validation and sanitization
- Secure handling of test cases

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## License

[Your License Here]
