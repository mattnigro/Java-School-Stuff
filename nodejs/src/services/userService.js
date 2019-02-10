import { resolve } from "url";

export class UserService{
    
    async getUserAsync(){
        resolve([{userId: 100}, {userId: 101}]);
    }
}