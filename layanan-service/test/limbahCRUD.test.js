const request = require("supertest");
const app = require("../app");
const { sequelize } = require("../models");

beforeAll(async () => await sequelize.sync({ force: true }));
afterAll(async () => await sequelize.close());

let id;

test("POST /limbah", async () => {
  const res = await request(app).post("/limbah")
    .send({ jenis: "Organik", jumlah: 10, lokasi: "Gudang A" });
  expect(res.status).toBe(201);
  id = res.body.id;
});

test("GET /limbah", async () => {
  const res = await request(app).get("/limbah");
  expect(res.status).toBe(200);
  expect(res.body.length).toBeGreaterThanOrEqual(1);
});

test("PUT /limbah/:id", async () => {
  const res = await request(app).put(`/limbah/${id}`).send({ jumlah: 30 });
  expect(res.status).toBe(200);
  expect(res.body.jumlah).toBe(30);
});

test("DELETE /limbah/:id", async () => {
  const res = await request(app).delete(`/limbah/${id}`);
  expect(res.status).toBe(200);
  expect(res.body.message).toBe("Deleted");
});
