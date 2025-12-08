const { Limbah } = require("../models");

exports.createLimbah = async (req, res) => {
  try {
    const { jenis, jumlah, lokasi } = req.body;
    if (!jenis || !jumlah || !lokasi)
      return res.status(400).json({ message: "All fields are required" });

    const limbah = await Limbah.create({ jenis, jumlah, lokasi });
    res.status(201).json(limbah);
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
};

exports.getLimbah = async (req, res) => {
  try {
    const limbah = await Limbah.findAll();
    res.status(200).json(limbah);
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
};

exports.updateLimbah = async (req, res) => {
  try {
    const { id } = req.params;
    const limbah = await Limbah.findByPk(id);
    if (!limbah) return res.status(404).json({ message: "Not found" });

    const { jenis, jumlah, lokasi } = req.body;
    if (jenis) limbah.jenis = jenis;
    if (jumlah) limbah.jumlah = jumlah;
    if (lokasi) limbah.lokasi = lokasi;

    await limbah.save();
    res.status(200).json(limbah);
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
};

exports.deleteLimbah = async (req, res) => {
  try {
    const { id } = req.params;
    const limbah = await Limbah.findByPk(id);
    if (!limbah) return res.status(404).json({ message: "Not found" });

    await limbah.destroy();
    res.status(200).json({ message: "Deleted" });
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
};
