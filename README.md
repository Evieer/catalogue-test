
# Catalogue Test

## Описание проекта

Это демонстрационный каталог товаров с иерархией категорий, сортировкой, пагинацией и отображением цен. Реализован с использованием Laravel, Bootstrap, Docker и MySQL.

## Функциональность

- Категории и подкатегории товаров
- Список товаров с сортировкой и пагинацией
- Отображение цен
- API для получения списка товаров с фильтрами
- Blade-шаблоны для визуализации
- Docker-окружение для быстрого запуска

## Архитектура

- **Frontend**: Blade-шаблоны, Bootstrap, JS (AJAX)
- **Backend**: Laravel, REST API
- **БД**: MySQL
- **Контейнеризация**: Docker + Docker Compose
- **Слои**:
  - `app/Models`: Eloquent-модели `Group`, `Product`, `Price`
  - `app/Http/Controllers`: контроллеры для веба и API
  - `resources/views`: Blade-шаблоны
  - `routes/web.php` и `routes/api.php`: маршруты

## Путь пользователя

1. Пользователь заходит на главную `/` — видит список всех категорий и товаров.
2. Кликает на категорию → `/group/{id}` — отображаются товары из выбранной и вложенных категорий.
3. Использует сортировку (например, по цене) — на фронте срабатывает JS и делает AJAX-запрос на API `/api/products`.
4. API возвращает отфильтрованный список товаров, который подгружается без перезагрузки страницы.

## Реализация сортировки и пагинации

В `app.blade.php` определены:

- Обработчики событий для `<select>` и пагинации
- `fetchProducts()` — отправляет AJAX-запрос на `/api/products?sort=...&page=...`
- В параметры запроса также передаются ID текущих групп
- Результат рендерится в контейнер с id `#products-container`
- Пагинация генерируется сервером и обновляется через JS

## Методы и контроллеры

### Контроллеры

#### `CatalogueController`

- `index()`: главная страница, выводит группы и товары
- `group($id)`: страница категории, строит хлебные крошки, подгруппы, товары
- `getNestedGroupIds()`: рекурсивно собирает ID подгрупп

#### `ProductApiController`

- `index(Request $request)`:
  - фильтрует товары по `groupIds`
  - сортирует по `price`, `name`
  - возвращает пагинированный список через `ProductResource`

### Модели

#### `Group`

- `products()`, `children()`, `parent()` — связи
- `getDirectProductsCountAttribute()` — количество товаров в этой категории
- `getProductsCountAttribute()` — включая подкатегории
- `getNestedGroupIds()` — вспомогательный метод

#### `Product`

- `group()`, `price()` — связи
- `scopeOrderByPrice()` — сортировка по цене через join

#### `Price`

- `product()` — принадлежит товару

## Как запустить

```bash
git clone ...
cd catalogue-test
cp .env.example .env
docker compose up -d
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
```

Приложение будет доступно на `http://localhost:8080`

## Документация API

GET `/api/products`

**Параметры:**

- `sort`: `price_asc`, `price_desc`, `name_asc`, `name_desc`
- `page`: номер страницы
- `per_page`: количество на странице
- `groupIds`: список ID через запятую

**Пример:**

```
/api/products?sort=price_asc&groupIds=1,2&per_page=12&page=1
```

Возвращает JSON-список товаров с пагинацией.

---

© 2025 Catalogue Test
