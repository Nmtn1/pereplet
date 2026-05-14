<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Url;

class Book extends ActiveRecord
{
    public static function tableName()
    {
        return 'books';
    }

    public function rules()
    {
        return [
            [['title', 'slug', 'price'], 'required'],
            [['description'], 'string'],
            [['price', 'old_price', 'rating', 'weight'], 'number'],
            [['discount_percent', 'stock', 'pages', 'year', 'age_restriction', 'rating_count', 'views_count', 'sales_count', 'author_id', 'publisher_id', 'category_id'], 'integer'],
            [['title', 'slug', 'isbn', 'cover_type'], 'string', 'max' => 255],
            [['format'], 'string', 'max' => 50],
            [['slug'], 'unique'],
            [['isbn'], 'unique'],
            [['is_bestseller', 'is_new'], 'boolean'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }
    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }

    public function getPublisher()
    {
        return $this->hasOne(Publisher::class, ['id' => 'publisher_id']);
    }

    public function getMainCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getImages()
    {
        return $this->hasMany(BookImage::class, ['book_id' => 'id'])->orderBy(['sort_order' => SORT_ASC]);
    }

    public function getMainImage()
    {
        return $this->hasOne(BookImage::class, ['book_id' => 'id'])
            ->where(['is_main' => 1])
            ->orderBy(['sort_order' => SORT_ASC]);
    }
    public function getImageUrl()
    {
    
        $mainImage = $this->getMainImage()->one();
        if ($mainImage && $mainImage->image_path) {
            return $mainImage->image_path;
        }
      
        $anyImage = $this->getImages()->one();
        if ($anyImage && $anyImage->image_path) {
            return $anyImage->image_path;
        }
        return '/img/books/default.jpg';
    }
    public function getRatingAverage()
    {
        if ($this->rating_count > 0) {
            return round($this->rating / $this->rating_count, 1);
        }
        return 0;
    }

    public function getStars()
    {
        $rating = $this->getRatingAverage();
        $full = floor($rating);
        $half = $rating - $full >= 0.5;
        $empty = 5 - $full - ($half ? 1 : 0);
        
        $stars = '';
        for ($i = 0; $i < $full; $i++) $stars .= '★';
        if ($half) $stars .= '½';
        for ($i = 0; $i < $empty; $i++) $stars .= '☆';
        
        return $stars;
    }

    public function getFinalPrice()
    {
        if ($this->old_price && $this->old_price > $this->price) {
            return $this->price;
        }
        if ($this->discount_percent > 0) {
            return $this->price * (100 - $this->discount_percent) / 100;
        }
        return $this->price;
    }

    public function getDiscountPercent()
    {
        if ($this->old_price && $this->old_price > $this->price) {
            return round(($this->old_price - $this->price) / $this->old_price * 100);
        }
        return $this->discount_percent;
    }

    public function getUrl()
    {
        return Url::to(['book/view', 'slug' => $this->slug]);
    }
    public function getReviews()
    {
        return $this->hasMany(Reviews::class, ['book_id' => 'id'])
            ->where(['is_approved' => 1])
            ->orderBy(['created_at' => SORT_DESC]);
    }
    public function getCategories()
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])
            ->viaTable('book_categories', ['book_id' => 'id']);
    }
}