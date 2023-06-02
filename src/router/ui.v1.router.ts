import EXPRESS, { Request, Response } from "express";

const uiV1Router = EXPRESS.Router();

// uiV1Router.get('/', function (req, res, next) {
//     console.log("User Router Working");
//     res.end();
// });


uiV1Router.get("/", (req, res) => {
    res.render("pages/index");
});

uiV1Router.get("/auth", (req, res) => {
    res.render("pages/auth");
});

uiV1Router.get("/register", (req, res) => {
    res.render("pages/register");
});

export { uiV1Router };