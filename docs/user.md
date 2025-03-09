# Documentation Api User

## Register
- Request:
    ``` 
    { 
        "name": string,
        "email": string,
        "password": string,
        "confirm_password": string
    } 
    ```
- Response:
    ``` 
    { 
        "message": string
    } 
    ```
    
## Login
- Request:
    ``` 
    { 
        "email": string,
        "password": string,
    } 
    ```
- Response:
    ``` 
    { 
        "message": string
    } 
    ```

## Get user
- Response:
    ``` 
    { 
        "data": {
            "name": string,
            "email": string,
        }
    } 
    ```
    
## Get user's news
- Response:
    ``` 
    { 
        "data": array
    } 
    ```