const express = require("express");
const morgan = require("morgan");
const limbahRoutes = require("./routes/limbahRoutes");
const correlationId = require("./middlewares/correlationId");

const app = express();

app.use(express.json());
app.use(morgan("dev"));
app.use(correlationId);

app.get("/health", (req, res) => {
  res.json({ status: "ok" });
});

app.use("/limbah", limbahRoutes);

module.exports = app;
