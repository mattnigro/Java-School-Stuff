import {Config} from './config/Config';
import {Server} from './servers/server';
import {UsersRouter} from './routers/usersRouter';
import {UserService} from './services/userService';

var config = new Config()
    .setPort(3000)
    .addTransient("UserService", UserService);

new Server(config)
    .addRouter("/api/users", new UsersRouter(config))
    .start();