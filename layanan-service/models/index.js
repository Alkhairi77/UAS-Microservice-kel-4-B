const { Sequelize, DataTypes } = require("sequelize");
const sequelize = new Sequelize({
  dialect: "sqlite",
  storage: "./database.sqlite"
});

const Limbah = sequelize.define("Limbah", {
  jenis: {
    type: DataTypes.STRING,
    allowNull: false
  },
  jumlah: {
    type: DataTypes.INTEGER,
    allowNull: false
  },
  lokasi: {
    type: DataTypes.STRING,
    allowNull: false
  }
});

module.exports = { sequelize, Limbah };
