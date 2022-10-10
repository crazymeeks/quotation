<div class="left side-menu">
                <button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect">
                    <i class="ion-close"></i>
                </button>

                <!-- LOGO -->
                <div class="topbar-left">
                    <div class="text-center bg-logo">
                        <a href="/home" class="logo"><i class="mdi mdi-bowling text-success"></i> AURORA PHILS. INC</a>
                    </div>
                </div>
                <div class="sidebar-user">
                    <img src="/assets/images/users/avatar-6.jpg" alt="user" class="rounded-circle img-thumbnail mb-1">
                    <h6 class="">{{session()->get('auth')->firstname}} </h6> 
                    <p class=" online-icon text-dark"><i class="mdi mdi-record text-success"></i>online</p>                    
                </div>

                <div class="sidebar-inner slimscrollleft">

                    <div id="sidebar-menu">
                        <ul>
                            <li class="menu-title">Main</li>

                            <li>
                                <a href="{{route('home')}}" class="waves-effect">
                                    <i class="dripicons-device-desktop"></i>
                                    <span> Home</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{route('admin.customer.index')}}" class="waves-effect"><i class="dripicons-user"></i> <span> Customers </span> <span class="float-right"></span></a>
                            </li>
                            <li>
                                <a href="{{route('admin.company.index')}}" class="waves-effect"><i class="fas fa-building"></i> <span> Companies </span> <span class="float-right"></span></a>
                            </li>
                            
                            <li>
                                <a href="{{route('admin.uom.index')}}" class="waves-effect"><i class="fas fa-ruler-horizontal"></i> <span> Unit of measures </span> <span class="float-right"></span></a>
                            </li>
                            <li>
                                <a href="{{route('product.index')}}" class="waves-effect"><i class="dripicons-stack"></i><span> Products</a>
                            </li>
                            <li>
                                <a href="{{route('admin.quotation.index')}}" class="waves-effect"><i class="fas fa-users-cog"></i> <span> Quotations </span></a>
                            </li>
                            <li>
                                <a href="{{route('admin.orders.get.index')}}" class="waves-effect"><i class="far fa-money-bill-alt"></i><span> Orders </span></a>
                            </li>
                            <li>
                                <a href="{{route('admin.role.get.index')}}" class="waves-effect"><i class="fas fa-gavel"></i> <span> Roles </span></a>
                            </li>
                            
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div> <!-- end sidebarinner -->
            </div>