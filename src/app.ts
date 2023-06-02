import EXPRESS from "express";
import { uiV1Router } from "./router/ui.v1.router";
import * as path from 'path'
import userRouter from "./router/user.router";

const APP = EXPRESS();
const PORT = 3000;


APP.set("view engine", "ejs")
APP.set("views", path.join(__dirname, "views"));
const publicDirectoryPath = path.join(__dirname, "./public");
APP.use(EXPRESS.static(publicDirectoryPath));

APP.use(EXPRESS.urlencoded({extended: true}));
APP.use(EXPRESS.json())

APP.use("/", uiV1Router);
APP.use("/users", userRouter);

// tslint:disable-next-line:no-console
console.log(`app is now listening ${PORT}`);

APP.listen(PORT);