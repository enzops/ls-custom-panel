/*
  Warnings:

  - Added the required column `UserActive` to the `User` table without a default value. This is not possible if the table is not empty.
  - Added the required column `UserAdmin` to the `User` table without a default value. This is not possible if the table is not empty.

*/
-- RedefineTables
PRAGMA foreign_keys=OFF;
CREATE TABLE "new_User" (
    "UserId" INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    "UserFirstName" TEXT NOT NULL,
    "UserLastName" TEXT NOT NULL,
    "UserPassword" TEXT NOT NULL,
    "UserPhone" TEXT,
    "UserEmail" TEXT NOT NULL,
    "UserLevel" INTEGER,
    "UserAdmin" BOOLEAN NOT NULL,
    "UserActive" BOOLEAN NOT NULL,
    CONSTRAINT "User_UserLevel_fkey" FOREIGN KEY ("UserLevel") REFERENCES "Levels" ("LevelId") ON DELETE SET NULL ON UPDATE CASCADE
);
INSERT INTO "new_User" ("UserEmail", "UserFirstName", "UserId", "UserLastName", "UserLevel", "UserPassword", "UserPhone") SELECT "UserEmail", "UserFirstName", "UserId", "UserLastName", "UserLevel", "UserPassword", "UserPhone" FROM "User";
DROP TABLE "User";
ALTER TABLE "new_User" RENAME TO "User";
CREATE UNIQUE INDEX "User_UserLevel_key" ON "User"("UserLevel");
PRAGMA foreign_key_check;
PRAGMA foreign_keys=ON;
