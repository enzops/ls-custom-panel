/*
  Warnings:

  - The primary key for the `User` table will be changed. If it partially fails, the table could be left without primary key constraint.
  - You are about to drop the column `email` on the `User` table. All the data in the column will be lost.
  - You are about to drop the column `id` on the `User` table. All the data in the column will be lost.
  - You are about to drop the column `name` on the `User` table. All the data in the column will be lost.
  - You are about to drop the column `password` on the `User` table. All the data in the column will be lost.
  - Added the required column `UserEmail` to the `User` table without a default value. This is not possible if the table is not empty.
  - Added the required column `UserFirstName` to the `User` table without a default value. This is not possible if the table is not empty.
  - Added the required column `UserId` to the `User` table without a default value. This is not possible if the table is not empty.
  - Added the required column `UserLastName` to the `User` table without a default value. This is not possible if the table is not empty.
  - Added the required column `UserLevel` to the `User` table without a default value. This is not possible if the table is not empty.
  - Added the required column `UserPassword` to the `User` table without a default value. This is not possible if the table is not empty.
  - Added the required column `UserPhone` to the `User` table without a default value. This is not possible if the table is not empty.

*/
-- CreateTable
CREATE TABLE "Levels" (
    "LevelId" INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    "LevelName" TEXT NOT NULL,
    "Level" INTEGER NOT NULL
);

-- RedefineTables
PRAGMA foreign_keys=OFF;
CREATE TABLE "new_User" (
    "UserId" INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    "UserFirstName" TEXT NOT NULL,
    "UserLastName" TEXT NOT NULL,
    "UserPassword" TEXT NOT NULL,
    "UserPhone" TEXT NOT NULL,
    "UserEmail" TEXT NOT NULL,
    "UserLevel" INTEGER NOT NULL,
    CONSTRAINT "User_UserLevel_fkey" FOREIGN KEY ("UserLevel") REFERENCES "Levels" ("LevelId") ON DELETE RESTRICT ON UPDATE CASCADE
);
DROP TABLE "User";
ALTER TABLE "new_User" RENAME TO "User";
CREATE UNIQUE INDEX "User_UserLevel_key" ON "User"("UserLevel");
PRAGMA foreign_key_check;
PRAGMA foreign_keys=ON;

-- CreateIndex
CREATE UNIQUE INDEX "Levels_LevelName_key" ON "Levels"("LevelName");

-- CreateIndex
CREATE UNIQUE INDEX "Levels_Level_key" ON "Levels"("Level");
