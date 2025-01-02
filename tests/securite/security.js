function sanitizeInput(input) {
    return input.replace(/<script.*?>.*?<\/script>/gi, '');
}

module.exports = { sanitizeInput };