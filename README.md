# TicTacToe API

PHP implementation of a TicTacToe game server.

## Requirements
- PHP 8.2+

## Setup
1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   ```
3. Start the Symfony server:
   ```bash
   symfony serve
   ```

## Usage
Make a GET request to the root endpoint with a board state:
```
GET /?board=x++++++++
```

Board notation:
- `x`: Player moves
- `o`: Server moves
- `+` or space: Empty position
- Board is read left-to-right, top-to-bottom

## Testing
Run tests with:
```bash
php bin/phpunit
```