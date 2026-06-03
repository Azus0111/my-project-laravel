@extends('client.layouts.index')
@section('title', 'Thanh toán')

@section('content')

    @php
        $cart = session('cart', []);
        $total = collect($cart)->sum(fn($i) => ($i['final_price'] ?? $i['price']) * $i['quantity']);
    @endphp

    <div class="max-w-5xl mx-auto px-4 py-6">

        <x-breadcrumbs :items="[
            ['title' => 'Trang chủ', 'url' => route('index')],
            ['title' => 'Giỏ hàng', 'url' => route('cart.index')],
            ['title' => 'Thanh toán'],
        ]" />

        <h2 class="text-xl font-bold text-base-content mt-4 mb-6">Thanh toán</h2>

        <form method="POST" action="{{ route('cart.save') }}">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-6 items-start">

                {{-- ===== THÔNG TIN GIAO HÀNG ===== --}}
                <div class="flex flex-col gap-4">
                    <div class="bg-base-100 border border-base-200 rounded-2xl p-5">
                        <p class="text-sm font-bold text-base-content mb-4">
                            Thông tin người nhận
                        </p>

                        {{-- Họ tên --}}
                        <fieldset class="fieldset">
                            <legend class="fieldset-legend text-xs">Họ và tên</legend>
                            <input type="text" name="fullname" value="{{ old('fullname') }}" placeholder="Nguyễn Văn A"
                                class="input input-bordered w-full rounded-xl
                                          @error('fullname') input-error @enderror" />
                            @error('fullname')
                                <p class="text-error text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </fieldset>

                        {{-- SĐT + Email --}}
                        <div class="grid grid-cols-2 sm:grid-cols-1 gap-3 mt-3">
                            <fieldset class="fieldset">
                                <legend class="fieldset-legend text-xs">Số điện thoại</legend>
                                <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="0912 345 678" class="input input-bordered w-full rounded-xl
                                              @error('phone') input-error @enderror" />
                                @error('phone')
                                    <p class="text-error text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </fieldset>

                            <fieldset class="fieldset">
                                <legend class="fieldset-legend text-xs">Email</legend>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="example@email.com"
                                    class="input input-bordered w-full rounded-xl
                                              @error('email') input-error @enderror" />
                                @error('email')
                                    <p class="text-error text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </fieldset>
                        </div>

                        <fieldset class="fieldset mt-3">
                            <legend class="fieldset-legend text-xs">Địa chỉ</legend>
                            <input type="text" name="address" value="{{ old('address') }}"
                                placeholder="123 Đường ABC, Quận XYZ, TP. HCM" class="input input-bordered w-full rounded-xl
                                          @error('address') input-error @enderror" />
                            @error('address')
                                <p class="text-error text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </fieldset>

                        <fieldset class="fieldset mt-3">
                            <legend class="fieldset-legend text-xs">Phương thức thanh toán</legend>
                            <select name="payment_method" id="payment_method"
                                class="select select-bordered w-full rounded-xl">
                                <option value="">Chọn phương thức</option>
                                <option value="cod">Thanh toán khi nhận hàng</option>
                                <option disabled value="bank_transfer">Chuyển khoản ngân hàng</option>
                            </select>
                        </fieldset>
                        {{-- Ghi chú --}}
                        <fieldset class="fieldset mt-3">
                            <legend class="fieldset-legend text-xs">Ghi chú</legend>
                            <textarea name="note" rows="3" placeholder="Ghi chú cho đơn hàng (tuỳ chọn)..."
                                class="textarea textarea-bordered w-full rounded-xl resize-none">{{ old('note') }}</textarea>
                        </fieldset>
                    </div>

                    {{-- ===== BẢNG SẢN PHẨM ===== --}}
                    <div class="bg-base-100 border border-base-200 rounded-2xl p-5">
                        <p class="text-sm font-bold text-base-content mb-4">
                            Sản phẩm trong đơn
                        </p>

                        @if (empty($cart))
                            <p class="text-sm text-base-content/40 text-center py-8">
                                Giỏ hàng trống.
                            </p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="table table-sm w-full">
                                    <thead>
                                        <tr class="text-xs text-base-content/40 border-base-200">
                                            <th class="font-medium">Sản phẩm</th>
                                            <th class="font-medium text-center">SL</th>
                                            <th class="font-medium text-right">Đơn giá</th>
                                            <th class="font-medium text-right">Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cart as $item)
                                            @php $price = $item['final_price'] ?? $item['price']; @endphp
                                            <tr class="border-base-200">
                                                <td class="text-sm font-medium text-base-content max-w-[200px]">
                                                    <span class="line-clamp-2">{{ $item['name'] }}</span>
                                                </td>
                                                <td class="text-center text-sm text-base-content/60">
                                                    {{ $item['quantity'] }}
                                                </td>
                                                <td class="text-right text-sm text-base-content/60 whitespace-nowrap">
                                                    {{ number_format($price, 0, ',', '.') }}đ
                                                </td>
                                                <td class="text-right text-sm font-semibold text-base-content whitespace-nowrap">
                                                    {{ number_format($price * $item['quantity'], 0, ',', '.') }}đ
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ===== TÓM TẮT ĐƠN HÀNG ===== --}}
                <div class="bg-base-100 border border-base-200 rounded-2xl p-5
                            flex flex-col gap-4 lg:sticky lg:top-20">

                    <p class="text-sm font-bold text-base-content">Tóm tắt đơn hàng</p>

                    <div class="flex flex-col gap-2 text-sm">
                        <div class="flex justify-between text-base-content/60">
                            <span>Tạm tính</span>
                            <span>{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="flex justify-between text-base-content/60">
                            <span>Phí vận chuyển</span>
                            <span class="text-success font-medium">Miễn phí</span>
                        </div>
                        <div class="divider my-1"></div>
                        <div class="flex justify-between font-bold text-base-content text-base">
                            <span>Tổng cộng</span>
                            <span class="text-error text-lg">
                                {{ number_format($total, 0, ',', '.') }}đ
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-error rounded-xl font-semibold w-full gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-4">
                            <path d="M20 12V22H4V12" />
                            <path d="M22 7H2v5h20V7z" />
                            <path d="M12 22V7" />
                            <path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z" />
                            <path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z" />
                        </svg>
                        Đặt hàng
                    </button>

                    <a href="{{ route('cart.index') }}"
                        class="btn btn-ghost btn-sm rounded-xl text-xs text-base-content/40">
                        ← Quay lại giỏ hàng
                    </a>
                </div>

            </div>
        </form>

    </div>

    @if ($errors->any())
        <x-toast type="error" :messages="$errors->all()" />
    @endif
    @if (session('success'))
        <x-toast type="success" :messages="[session('success')]" />
    @endif

@endsection