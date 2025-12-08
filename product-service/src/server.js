const app = require("./app");
const { initDB } = require("./models");

async function start() {
  await initDB();
  app.listen(3001, () => console.log("Product service running on port 3001"));
}

start();
