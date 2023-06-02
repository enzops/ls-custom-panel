import { Router } from "express";
import { User, PrismaClient } from "@prisma/client";
import {
    createUser,
    getAllUsers
} from "../controllers/users.controller";
const prisma = new PrismaClient()
const userClient = new PrismaClient().user;
const userRouter = Router();

userRouter.get("/", getAllUsers)

userRouter.post('/create', async (req, res) => {
    const { UserFirstName, UserLastName, UserEmail, UserPassword, UserPhone, UserLevel } = req.body
    const post = await userClient.create({
        data: {
            UserFirstName,
            UserLastName,
            UserEmail,
            UserPassword,
            UserPhone,
            UserLevel,
            UserAdmin: false,
            UserActive: false,
        },
    })
    res.json(post)
})

export default userRouter;