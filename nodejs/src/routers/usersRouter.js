var express = require('express');
import {UserService} from '../services/userService'; // <== ERROR cannot find

export class UsersRouter{
    constructor(config){
        this.config = config;
        this.userService = config.getUserService("UserService");
        
        if (!this.userService) throw Error("UserService not found");
    }

    build(){
        this.router = express.Router();
        this.router.route("/")
            .get(async(req, res, next) => {
                var users = await this.userService.getUsersAsync();
                res.json(users);
            });
        return this.router;
    }
}