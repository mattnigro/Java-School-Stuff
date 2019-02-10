const express = require("express");
const bodyParser = require("body-parser");

export class Server{
    constructor(config){
        this.config = config;
        this.app = express();
        this.app.use(bodyParser.json());
    }

    addRouter(path, router){
        this.app.use(path, router.build());
        return this;
    }

    start(){
        this.app.listen(this.config.getPort(), () => console.log(`Listening on port ${this.config.getPort()}`));
    }
}