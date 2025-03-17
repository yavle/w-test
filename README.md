# Test case 

Based on [symfony-docker](https://github.com/dunglas/symfony-docker) repo

## Run sequence

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `docker compose build --no-cache` to build fresh images
3. Run `docker compose up -d` 
4. Run make sf c='app:import-xml http://localhost/products.xml' for test import (use custom url and/or optional -bXXX parameter for batch size) 
5. Visit https://localhost/categories and https://localhost/categories/{id}/products as described in test requirements