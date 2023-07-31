// ===============================================
// SIAKAD FUNCTIONS BY INSHO
// ===============================================
function get_huruf_mutu(n) {
  if (isNaN(n) || n < 0) return "NULL";
  if (n >= 85) return "A";
  if (n >= 70) return "B";
  if (n >= 55) return "C";
  if (n >= 40) return "D";
  return "E";
}
