    <div class="left-side-menu">

                <div class="h-100" data-simplebar>

                    <!-- User box -->


                    <!--- Sidemenu -->
                    <div id="sidebar-menu">

                        <ul id="side-menu">



          <li>
            <a href="{{ url('/dashboard') }}">
               <i class="mdi mdi-view-dashboard-outline"></i>
                <span> Trang chủ </span>
            </a>
        </li>


        {{-- @if(Auth::user()->can('pos.menu')) --}}
           <li>
            <a href="{{ route('pos') }}">
               <i class="fas fa-cash-register"></i>
                <span> POS </span>
            </a>
        </li>
        {{-- @endif --}}



 {{-- @if(Auth::user()->can('product.menu')) --}}
         <li>
            <a href="#product" data-bs-toggle="collapse">
                <i class="fab fa-product-hunt"></i>
                <span> Sản phẩm  </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="product">
                <ul class="nav-second-level">
                    <li>
                        <a href="{{ route('all.category') }}">Loại sản phẩm </a>
                    </li>
                    <li>
                        <a href="{{ route('all.product') }}">Sản phẩm </a>
                    </li>
                </ul>
            </div>
        </li>

{{-- @endif --}}
 {{-- @if(Auth::user()->can('orders.menu')) --}}

                            <li>
                                <a href="{{ route('complete.order') }}">
                                    <i class="fas fa-file-invoice"></i>
                                    <span> Hóa đơn </span>
                                </a>
                            </li>
{{-- @endif --}}
<!--                            <li>-->
<!--                                <a href="{{ route('all.discount') }}">-->
<!--                                    <i class="fas fa-percent"></i>-->
<!--                                    <span> Mã giảm giá </span>-->
<!--                                </a>-->
<!--                            </li>-->

                                                        <li>
                                                            <a href="#discount" data-bs-toggle="collapse">
                                                                <i class="fas fa-percent"></i>
                                                                <span> Mã giảm giá   </span>
                                                                <span class="menu-arrow"></span>
                                                            </a>
                                                            <div class="collapse" id="discount">
                                                                <ul class="nav-second-level">
                                                                    <li>
                                                                        <a href="{{ route('all.discount') }}">Mã giảm giá</a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="{{ route('order.discount') }}">Hóa đơn áp dụng mã</a>
                                                                    </li>

                                                                </ul>
                                                            </div>
                                                        </li>

                            {{-- @if(Auth::user()->can('employee.menu')) --}}
                            <li>
                                <a href="#sidebarEcommerce" data-bs-toggle="collapse">
                                    <i class="mdi mdi-account-multiple-outline"></i>
                                    <span> Người dùng</span>
                                    <span class="menu-arrow"></span>
                                </a>
                                <div class="collapse" id="sidebarEcommerce">
                                    <ul class="nav-second-level">
                                        {{-- @if(Auth::user()->can('employee.all')) --}}
                                        <li>
                                            <a href="{{ route('all.employee') }}">Nhân viên</a>
                                        </li>
                                        {{-- @endif --}}
                                        {{-- @if(Auth::user()->can('customer.all')) --}}
<!--                                        <li>-->
<!--                                            <a href="{{ route('all.customer') }}">Khách hàng</a>-->
<!--                                        </li>-->
                                        {{-- @endif --}}
                                        {{-- @if(Auth::user()->can('supplier.all')) --}}
                                        <li>
                                            <a href="{{ route('all.supplier') }}">Nhà cung cấp</a>
                                        </li>
                                        {{-- @endif --}}
                                    </ul>

                                </div>
                            </li>
                            {{-- @endif --}}

                            <li>
                                <a href="{{ route('all.post') }}">
                                    <i class="fas fa-newspaper"></i>
                                    <span> Bài viết </span>
                                </a>
                            </li>

<!--                            @if(Auth::user()->can('stock.menu'))-->
<!--                            <li>-->
<!--                                <a href="#stock" data-bs-toggle="collapse">-->
<!--                                    <i class="mdi mdi-email-multiple-outline"></i>-->
<!--                                    <span> Stock Manage   </span>-->
<!--                                    <span class="menu-arrow"></span>-->
<!--                                </a>-->
<!--                                <div class="collapse" id="stock">-->
<!--                                    <ul class="nav-second-level">-->
<!--                                        <li>-->
<!--                                            <a href="{{ route('stock.manage') }}">Stock </a>-->
<!--                                        </li>-->
<!---->
<!---->
<!--                                    </ul>-->
<!--                                </div>-->
<!--                            </li>-->
<!--                            @endif-->
                            <li>
                                <a href="{{ route('report_invoice') }}">
                                    <i class="fas fa-chart-line"></i>
                                    <span> Báo cáo thống kê </span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('all.banner') }}">
                                    <i class="fas fa-sliders-h"></i>
                                    <span> Banner </span>
                                </a>
                            </li>
<!-- <li>-->
<!--    <a href="#permission" data-bs-toggle="collapse">-->
<!--        <i class="fas fa-key"></i>-->
<!--        <span> Roles And Permission    </span>-->
<!--        <span class="menu-arrow"></span>-->
<!--    </a>-->
<!--    <div class="collapse" id="permission">-->
<!--        <ul class="nav-second-level">-->
<!--            <li>-->
<!--                <a href="{{ route('all.permission') }}">All Permission </a>-->
<!--            </li>-->
<!---->
<!--            <li>-->
<!--                <a href="{{ route('all.roles') }}">All Roles </a>-->
<!--            </li>-->
<!---->
<!--             <li>-->
<!--                <a href="{{ route('add.roles.permission') }}">Roles in Permission </a>-->
<!--            </li>-->
<!---->
<!--             <li>-->
<!--                <a href="{{ route('all.roles.permission') }}">All Roles in Permission </a>-->
<!--            </li>-->
<!---->
<!---->
<!--        </ul>-->
<!--    </div>-->
<!--</li>-->

                            <li>
                                <a href="{{ route('all.admin') }}">
                                    <i class="mdi mdi-account-settings"></i>
                                    <span> Tài khoản </span>
                                </a>
                            </li>











                                    </ul>
                                </div>
                            </li>
                        </ul>

                    </div>
                    <!-- End Sidebar -->

                    <div class="clearfix"></div>

                </div>
                <!-- Sidebar -left -->

            </div>
