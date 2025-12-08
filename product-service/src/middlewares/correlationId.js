const { v4: uuidv4 } = require("uuid");

module.exports = function correlationId(req, res, next) {
  const cid = req.headers["x-correlation-id"] || uuidv4();

  req.correlationId = cid;
  res.setHeader("x-correlation-id", cid);

  next();
};
