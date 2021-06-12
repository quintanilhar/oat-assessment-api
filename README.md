# Assessment Api

The easist way to manage multiple-choice questions in your assessments.

## Built With

* [PHP](https://www.php.net/)
* [OpenAPI Spec](https://swagger.io/specification/)
* [Slim](https://www.slimframework.com/)

## Getting Started

To get a local copy up and running follow these simple steps.

### Prerequisites

* [Docker Compose](https://docs.docker.com/compose/compose-file/): > 3.8
* [Make](https://www.gnu.org/software/make/manual/make.html#Introduction)
    * There is a [thread on StackOverflow](https://stackoverflow.com/questions/32127524/how-to-install-and-use-make-in-windows) with some alternative for Windows users.

### Installation

1. Clone the repo
   ```sh
   git clone https://github.com/quintanilhar/oat-assessment-api.git
   ```
2. Start up the app
   ```sh
   make up
   ```
3. Run tests
   ```sh
   make test
   ```

## Usage

### Creating a question

**Request**
```sh
   curl -XPOST 'http://oat-x.docker:9001/v1/questions' \
      --header 'Content-Type: application/json' \
      --data-raw '{
         "text": "What is the capital of Luxembourg?",
         "choices": [
            "Viena",
            "Madri",
            "Luxembourg"
         ]
      }'

```

**Response**
```js
{
    "text": "What is the capital of Luxembourg?",
    "createdAt": "2021-06-12T21:42:15+02:00",
    "choices": [
        "Viena",
        "Madri",
        "Luxembourg"
    ]
}
```

### Listing questions

**Request**
```sh
   curl -XGET 'http://oat-x.docker:9001/v1/questions?lang=en' \
      --header 'Accept: application/json'
```

**Response**
```js
[
   {
        "text": "What is the capital of Luxembourg?",
        "createdAt": "2021-06-12T18:03:47+02:00",
        "choices": [
            "Viena",
            "Madri",
            "Luxembourg"
        ]
    }
]
```

You can find a detailed documentation about the endpoints available in the [specification](doc/open-api.yaml).