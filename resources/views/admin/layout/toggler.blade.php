@if (session('role_slug') == 'merch_admin' || 
    session('role_slug') == 'merch_procurement' || 
    session('role_slug') == 'merch_commercial' ||  
    session('role_slug') == 'merch_accounts' ||  
    session('role_slug') == 'merch_m_w_procurement' ||  
    session('role_slug') == 'merch_m_wo_procurement'  ||  
    session('role_slug') == 'merch_marketing')
    <div class="toggle-btn-area">
        <button type="button" data-url="{{route('admin.merchantRoleUpdate','buyer')}}" class="toggle-button btn @if (session('merchant_role') == 'buyer') active @endif" @if ($merchant['type'] != 'both') disabled @endif id="buyer">Buyer</button>
        <button type="button" data-url="{{route('admin.merchantRoleUpdate','seller')}}" class="toggle-button btn @if (session('merchant_role') == 'seller') active @endif" @if ($merchant['type'] != 'both') disabled @endif  id="seller">Seller</button>
    </div>
@endif