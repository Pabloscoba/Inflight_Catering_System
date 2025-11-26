<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Categories</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f5f5f5; }
        
        .container { max-width: 1200px; margin: 0 auto; padding: 40px 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { font-size: 28px; font-weight: 700; color: #000; }
        .header-actions { display: flex; gap: 12px; }
        .btn { padding: 10px 20px; border-radius: 6px; font-size: 14px; font-weight: 500; text-decoration: none; border: none; cursor: pointer; }
        .btn-primary { background: #0066cc; color: #fff; }
        .btn-primary:hover { background: #0052a3; }
        .btn-secondary { background: #6c757d; color: #fff; }
        .btn-secondary:hover { background: #5a6268; }
        .btn-danger { background: #dc3545; color: #fff; }
        .btn-sm { padding: 6px 12px; font-size: 13px; }
        
        .alert { padding: 12px 20px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        
        .categories-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
        .category-card { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 24px; transition: box-shadow 0.2s; }
        .category-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .category-header { display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px; }
        .category-name { font-size: 20px; font-weight: 600; color: #0b1a68; margin-bottom: 4px; }
        .category-badge { padding: 4px 10px; background: #e3f2fd; color: #0066cc; border-radius: 12px; font-size: 12px; font-weight: 600; }
        .category-description { color: #6c757d; font-size: 14px; line-height: 1.5; margin-bottom: 16px; }
        .category-actions { display: flex; gap: 8px; padding-top: 16px; border-top: 1px solid #e9ecef; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Product Categories</h1>
            <div class="header-actions">
                <a href="{{ route('admin.products.index') }}" class="btn btn-primary">View Products</a>
                <a href="{{ route('dashboard.index') }}" class="btn btn-secondary">← Back</a>
            </div>
        </div>
        
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        
        <div class="categories-grid">
            @foreach($categories as $category)
            <div class="category-card">
                <div class="category-header">
                    <div>
                        <div class="category-name">{{ $category->name }}</div>
                    </div>
                    <span class="category-badge">{{ $category->products_count }} Products</span>
                </div>
                <p class="category-description">{{ $category->description ?? 'No description available' }}</p>
                <div class="category-actions">
                    <a href="{{ route('admin.products.index', ['category' => $category->id]) }}" class="btn btn-primary btn-sm">View Products</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</body>
</html>
