# Documentation Api News

## Create news
- Request:
    ``` 
    { 
        "thumbnail": string,
        "pictures": array,
        "title": string,
        "slug": string,
        "excerpt": string,
        "body": string
    } 
    ```
    
- Response Failed:
    - Status Code 400
        ``` 
        { 
            "errors": array
        } 
        ```
- Response Success:
    ``` 
    { 
        "message": string
    } 
    ```
    
## Get many news
- Response Failed:
    - Status Code 404
        ``` 
        { 
            "message": string
        } 
        ```
- Response Success:
    ``` 
    { 
        "data": array
    } 
    ```
    
## Get one news
- Response Failed:
    - Status Code 404
        ``` 
        { 
            "message": string
        } 
        ```
- Response Success:
    ``` 
    { 
        "data": {
            "thumbnail": string,
            "pictures": array,
            "title": string,
            "slug": string,
            "excerpt": string,
            "body": string,
            "createdAt": Date,
            "updatedAt": Date
        }
    } 
    ```
    
## Update news
- Request:
    ``` 
    { 
        "thumbnail": string,
        "pictures": array,
        "title": string,
        "slug": string,
        "excerpt": string,
        "body": string
    } 
    ```
- Response Failed:
    - Status Code 400
        ``` 
        { 
            "errors": array
        } 
        ```
    
- Response Success:
    ``` 
    { 
        "message": string
    } 
    ```
    
## Delete news
- Response Failed:
    - Status Code 404
        ``` 
        { 
            "message": string
        } 
        ```
- Response Success:
    ``` 
    { 
        "message": string
    } 
    ```
    
    