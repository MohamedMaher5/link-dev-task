# Link Dev Task

## Getting Started

### Prerequisites
- Docker
- Docker Compose

### Running the Application

Start the application using Docker Compose:

```bash
docker compose -f docker-compose.dev.yml up -d
```

### Access Points

- **Application URL**: http://127.0.0.1:4000
- **API Documentation**: http://127.0.0.1:4000/docs/api#

### API Testing

#### Postman Collection

A Postman collection is available for testing the API endpoints:

1. Import the collection file: `Link-Dev-Task.postman_collection.json` (located in `/docs` folder)
2. Import the environment file: `Link-Dev-Task-Env.postman_environment.json` (located in `/docs` folder)
3. Set the base URL environment variable to: `http://127.0.0.1:4000`
4. Start making requests to test the API

Alternatively, you can use the API documentation at http://127.0.0.1:4000/docs/api# to explore and test endpoints directly in your browser.

### Stopping the Application

```bash
docker compose -f docker-compose.dev.yml down
```
