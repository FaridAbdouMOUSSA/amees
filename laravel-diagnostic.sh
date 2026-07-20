#!/bin/bash
echo "🔍 LARAVEL FULL DIAGNOSTIC - $(date)" > diagnostic.txt
echo "================================================" >> diagnostic.txt

# 1. ENVIRONNEMENT
echo "📊 ENVIRONNEMENT" >> diagnostic.txt
php --version >> diagnostic.txt 2>&1
composer --version >> diagnostic.txt 2>&1
php artisan --version >> diagnostic.txt 2>&1
php artisan about >> diagnostic.txt 2>&1
echo "" >> diagnostic.txt

# 2. STRUCTURE
echo "📁 STRUCTURE" >> diagnostic.txt
find . -name "*.php" -type f | head -20 >> diagnostic.txt
echo "Modèles:" >> diagnostic.txt
ls -la app/Models/ >> diagnostic.txt 2>&1
echo "" >> diagnostic.txt

# 3. ROUTES
echo "🛣️ ROUTES ADMIN" >> diagnostic.txt
php artisan route:list --name=admin >> diagnostic.txt 2>&1 || echo "Erreur routes" >> diagnostic.txt
echo "" >> diagnostic.txt

# 4. MIGRATIONS
echo "🗄️ MIGRATIONS" >> diagnostic.txt
php artisan migrate:status >> diagnostic.txt 2>&1
echo "" >> diagnostic.txt

# 5. CACHE
echo "🧹 CACHE CLEAR" >> diagnostic.txt
php artisan optimize:clear >> diagnostic.txt 2>&1
echo "" >> diagnostic.txt

# 6. BDD TESTS
echo "💾 BASE DONNÉES" >> diagnostic.txt
php artisan tinker --execute="echo 'Tables count: ' . DB::table('information_schema.tables')->where('table_schema', env('DB_DATABASE'))->count();" >> diagnostic.txt 2>&1 || echo "Tinker KO" >> diagnostic.txt
php artisan tinker --execute="echo class_exists('App\\\\Models\\\\User') ? 'User OK' : 'User KO';" >> diagnostic.txt 2>&1 || echo "User KO" >> diagnostic.txt
php artisan tinker --execute="echo class_exists('App\\\\Models\\\\Epreuve') ? 'Epreuve OK' : 'Epreuve KO';" >> diagnostic.txt 2>&1 || echo "Epreuve KO" >> diagnostic.txt
echo "" >> diagnostic.txt

# 7. FICHIERS CRITIQUES
echo "📄 FICHIERS CRITIQUES" >> diagnostic.txt
echo "=== RankingController.php ===" >> diagnostic.txt
head -50 app/Http/Controllers/RankingController.php >> diagnostic.txt 2>&1 || echo "RankingController absent" >> diagnostic.txt
echo "" >> diagnostic.txt

echo "=== User.php ===" >> diagnostic.txt
cat app/Models/User.php >> diagnostic.txt 2>&1 || echo "User.php absent" >> diagnostic.txt
echo "" >> diagnostic.txt

echo "=== Epreuve.php ===" >> diagnostic.txt
cat app/Models/Epreuve.php >> diagnostic.txt 2>&1 || echo "Epreuve.php absent" >> diagnostic.txt
echo "" >> diagnostic.txt

# 8. LOGS RÉCENTS
echo "📜 LOGS (20 dernières lignes)" >> diagnostic.txt
tail -20 storage/logs/laravel.log >> diagnostic.txt 2>&1 || echo "Logs vides" >> diagnostic.txt
echo "" >> diagnostic.txt

# 9. .ENV (anonymisé)
echo "🔧 .ENV (anonymisé)" >> diagnostic.txt
grep -E "(DB_|APP_|MAIL_)" .env 2>/dev/null | sed 's/=.*/=XXX/' >> diagnostic.txt || echo ".env absent" >> diagnostic.txt
echo "" >> diagnostic.txt

# 10. COMPOSER
echo "📦 COMPOSER" >> diagnostic.txt
composer show >> diagnostic.txt 2>&1 || echo "Composer KO" >> diagnostic.txt
echo "" >> diagnostic.txt

echo "✅ DIAGNOSTIC TERMINÉ ! Copie diagnostic.txt" >> diagnostic.txt
echo "Fichier créé : diagnostic.txt ($(du -h diagnostic.txt | cut -f1))"
