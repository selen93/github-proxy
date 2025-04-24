# Github Proxy

This is a simple PHP API that proxies the Github User Search with cURL


## Features

- Search for git hub users with a GET query. For example: "/search?q=Name"
- In order to show complete results, this proxy also supports a page parameter in the query. Adding "&page=3" will show the third page of the results. This way, it is possible to show all results from the GitHub API.
- Easy to deploy in a Docker Environment

## Requirements

- PHP
- Docker
- [GitHub Personal Access Token](5https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/managing-your-personal-access-tokens)

## Usage

### 1. Clone the Repo
```
git clone https://github.com/selen93/github-proxy.git
cd github-proxy
```

### 2. Add your GitHub Access Token to the env

Either copy the env-example and add your token or use
```
echo <YOUR TOKEN> >> .env
```

### 3. Start with Docker
Linux
```
docker-compose up -d
```
Mac
```
docker compose up -d
```

### 4. Query the Proxy
Either use a browser and go to `localhost:8080` and add `?query=Name` or use
```
GET http://localhost:8080/search?q=Name
```
And to see more results use the page parameter like
```
GET http://localhost:8080/serach?q=Name&page=5
```


## Sample response
```
{
    "query": "Searched for Nathalie",
    "result": [
      "nathalie",
      "nathaliebfm",
      "nccshecodes",
      "nathaliejanep",
      "codevea",
      "kalinathalie",
      ...
    ]
}
