const { v4: uuidv4 } = require("uuid").default || require("uuid");

module.exports = (req, res, next) => {
  req.correlationId = uuidv4();
  next();
};