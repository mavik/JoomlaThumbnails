---
trigger: always_on
---

# AI Rules

1. Do not modify files in the `joomla` directory.
2. Do not modify files in `mavik-thumbnails/libraries/masterminds/html5-php`
3. Do not add `@param` or `@return` to PHPDoc if it doesn't add new information that is not already present in the function declaration (e.g., if types are already specified).
4. Do not include the name of the entity being documented (e.g., "Class Name", "Method name") in the PHPDoc as it is redundant.
