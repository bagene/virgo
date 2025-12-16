## Test trading app

### Setup

- Build the Docker image:

  ```bash
  docker build -t test-trading-app .
  ```
  
- Run the Docker container:

  ```bash
    docker run -d -p 8000:8000 --name test-trading-app-container test-trading-app
    ```
- Access the application at `http://localhost:8000`.
- To stop the container, use:

  ```bash
  docker stop test-trading-app-container
  ```
  
- Run lint and phpstan

  ```bash
  docker exec -it test-trading-app-container composer lint
  ```
  
                                                          
### Pages

- Authentication: `/login`, `/register`
- Profile: `/`
  - View Balance, assets, and orders
- Orders: `/order`
  - Create Buy or Sell order

