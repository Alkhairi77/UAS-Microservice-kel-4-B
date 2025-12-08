module.exports = function errorHandler(err, req, res, next) {
  console.error("ERROR:", err.message);

  res.status(err.status || 500).json({
    status: "error",
    message: err.message || "Internal server error",
    correlationId: req.correlationId
  });
};
