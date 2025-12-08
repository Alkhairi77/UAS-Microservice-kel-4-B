const express = require("express");
const morgan = require("morgan");
const productRoutes = require("./routes/productRoutes");
const correlationId = require("./middlewares/correlationId");

const app = express();

app.use(express.json());
app.use(morgan("dev"));
app.use(correlationId);

// health check
app.get("/health", (req, res) => {
  res.json({ status: "ok" });
});

// routes
app.use("/products", productRoutes);

module.exports = app;
