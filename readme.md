symfony console make:migration
symfony console doctrine:migrations:migrate

curl -v -H "Content-Type: application/json" -X POST -d "{\"username\":\"aarao.melo.lopes@gmail.com\",\"password\":\"123\"}" http://localhost:8000/api/login_check