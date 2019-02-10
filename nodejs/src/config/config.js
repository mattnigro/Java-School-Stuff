export class Config{

    addTransient(key, cls){
        this[key] = cls;
        return this;
    }

    getService(key){
        return new this[key]();
    }

    setPort(port){
        this.port = port;
        return this;
    }

    getPort(){
        return this.port;
    }
}