# Tradernet interview test task

## License
[MIT license](https://opensource.org/licenses/MIT)

## Installation
1. git clone
2. docker-compose up -d
3. docker exec -it tradernet_app bash
4. make
5. add "127.0.0.1       tradernet.test" into your hosts file (or use 127.0.0.1 as domain name instead)

## Use example
curl -i -H "Accept: application/json" -H "Content-Type: application/json" -X GET "http://tradernet.test/api/rate?date=2020-10-3&quoteCurrency=USD&baseCurrency=RUR"
curl -i -H "Accept: application/json" -H "Content-Type: application/json" -X GET "http://tradernet.test/api/rate?date=2020-10-3&quoteCurrency=USD"
curl -i -H "Accept: application/json" -H "Content-Type: application/json" -X GET "http://tradernet.test/api/rate?date=2020-10-3&quoteCurrency=RUR&baseCurrency=USD"
curl -i -H "Accept: application/json" -H "Content-Type: application/json" -X GET "http://tradernet.test/api/rate?date=2020-10-3&quoteCurrency=EUR&baseCurrency=USD"