#!/bin/bash
echo "🔥 LARAVEL COPY-ALL - $(date)" > laravel-all.txt
echo "================================================" >> laravel-all.txt

# HEADER
cat << EOF >> laravel-all.txt
📊 ENVIRONNEMENT
\`\`\`bash
$(php --version 2>&1)
$(php artisan --version 2>&1)
$(php artisan about 2>&1)
\`\`\`
EOF

# ROUTES
echo "🛣️ ROUTES ADMIN" >> laravel-all.txt
echo "\`\`\`bash" >> laravel-all.txt
php artisan route:list --name=admin >> laravel-all.txt 2>&1
echo "\`\`\`" >> laravel-all.txt

# MIGRATIONS
echo -e "\n🗄️ MIGRATIONS" >> laravel-all.txt
echo "\`\`\`bash" >> laravel-all.txt
php artisan migrate:status >> laravel-all.txt 2>&1
echo "\`\`\`" >> laravel-all.txt

# FICHIERS CRITIQUES (CONTENU COMPLET)
FILES=(
    "app/Http/Controllers/RankingController.php"
    "app/Models/User.php"
    "app/Models/Epreuve.php"
    "app/Http/Controllers/AdminController.php"
    "app/Http/Middleware/Admin.php"
    "routes/web.php"
    "database/migrations/*epreuves*"
)

for file in "${FILES[@]}"; do
    if ls $file 1> /dev/null 2>&1; then
        echo -e "\n📄 $file" >> laravel-all.txt
        echo "\`\`\`php" >> laravel-all.txt
        cat $file >> laravel-all.txt 2>&1
        echo "\`\`\`" >> laravel-all.txt
    else
        echo -e "\n❌ $file ABSENT" >> laravel-all.txt
    fi
done

# LOGS
echo -e "\n📜 LOGS RÉCENTS" >> laravel-all.txt
echo "\`\`\`log" >> laravel-all.txt
tail -50 storage/logs/laravel.log >> laravel-all.txt 2>&1 || echo "Logs vides" >> laravel-all.txt
echo "\`\`\`" >> laravel-all.txt

# TESTS BDD
echo -e "\n💾 TESTS BDD" >> laravel-all.txt
echo "\`\`\`bash" >> laravel-all.txt
php artisan tinker --execute="echo 'User count: ' . App\Models\User::count();" >> laravel-all.txt 2>&1 || echo "Tinker KO" >> laravel-all.txt
php artisan tinker --execute="echo 'Epreuve count: ' . App\Models\Epreuve::count();" >> laravel-all.txt 2>&1 || echo "Epreuve KO" >> laravel-all.txt
echo "\`\`\`" >> laravel-all.txt

# CACHE
echo -e "\n🧹 CACHE CLEAR" >> laravel-all.txt
php artisan optimize:clear >> laravel-all.txt 2>&1

echo -e "\n✅ FICHIER PRÊT : laravel-all.txt ($(du -h laravel-all.txt | cut -f1))" >> laravel-all.txt
echo "📋 COPIE TOUT LE CONTENU → Colle chez Blackbox AI"
