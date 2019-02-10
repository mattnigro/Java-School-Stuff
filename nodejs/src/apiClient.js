import {Request} from 'request-promise-native';

export class ApiClient{
    getTasks(){
        return new Promise((resolve, reject) => {

            var options = {
                method: "GET",
                url: "https://jsonplaceholder.typicode.com/todos",
                json: true
            }
            new Request(options)
                .then(response => {
                    resolve(response);
                })
                .catch(err => {
                    reject(err);
                })
        });
    }
}