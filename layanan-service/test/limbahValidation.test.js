const request = require("supertest");
const app = require("../app");
const { sequelize } = require("../models");

beforeAll(async () => await sequelize.sync({ force: true }));
afterAll(async () => await sequelize.close());

test("POST /limbah with missing fields returns 400", async () => {
  const res = await request(app).post("/limbah").send({ jenis: "Organik" });
  expect(res.status).toBe(400);
  expect(res.body.message).toBe("All fields are required");
});
