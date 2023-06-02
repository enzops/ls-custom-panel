import { Request, Response } from "express";
import { User, PrismaClient } from "@prisma/client";

const userClient = new PrismaClient().user;

export const getAllUsers = async (
    req: Request,
    res: Response
): Promise<void> => {
    try {
        const allUsers: User[] = await userClient.findMany()

        res.status(200).json({ data: allUsers });
    } catch (error) {
        // tslint:disable-next-line:no-console
        console.log(error);
    }
};

export const createUser = async (
    req: Request,
    res: Response
): Promise<void> => {
    try {
        const userData = req.body;
        const user = await userClient.create({
            data: { ...userData },
        });

        res.status(201).json({ data: user });
    } catch ( error ) {
        // tslint:disable-next-line:no-console
        console.log(error);
    }
}