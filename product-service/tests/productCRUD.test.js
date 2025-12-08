const request = require("supertest");
const app = require("../src/app");
const { Product } = require("../src/models");

beforeAll(async () => {
  await Product.sync({ force: true }); // reset db
});

describe("Product CRUD", () => {
  let productId;

  it("should create a product", async () => {
    const res = await request(app)
      .post("/products")
      .send({ name: "Laptop", price: 15000 });

    expect(res.status).toBe(201);
    expect(res.body).toHaveProperty("id");
    productId = res.body.id;
  });

  it("should get all products", async () => {
    const res = await request(app).get("/products");
    expect(res.status).toBe(200);
    expect(Array.isArray(res.body)).toBe(true);
  });

  it("should update a product", async () => {
    const res = await request(app)
      .put(`/products/${productId}`)
      .send({ name: "Laptop Update", price: 20000 });

    expect(res.status).toBe(200);
    expect(res.body.message).toBe("Product updated");
  });

  it("should delete a product", async () => {
    const res = await request(app).delete(`/products/${productId}`);
    expect(res.status).toBe(200);
    expect(res.body.message).toBe("Product deleted");
  });
});
