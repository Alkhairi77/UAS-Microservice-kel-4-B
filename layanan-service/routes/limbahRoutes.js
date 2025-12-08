const express = require("express");
const router = express.Router();
const limbahController = require("../controllers/limbahController");

router.post("/", limbahController.createLimbah);
router.get("/", limbahController.getLimbah);
router.put("/:id", limbahController.updateLimbah);
router.delete("/:id", limbahController.deleteLimbah);

module.exports = router;
