$(document).ready(function() {
    // 비회원 주문 시스템 클래스
    class NonMemberOrderSystem {
        constructor() {
            // 요소 참조
            this.$modal = $('#nonMemberOrderModal');
            this.$modalOverlay = $('.modal-overlay');
            this.$orderArea = $('#orderArea');
            this.$orderProductList = $('#orderProductList');
            this.$guestId = $('#guestId');
            this.$emptyCartMessage = $('.empty-cart');
            this.$removeInstruction = $('.remove-instruction');
            this.$totalProductAmount = $('#totalProductAmount');
            this.$shippingFee = $('#shippingFee');
            this.$totalPaymentAmount = $('#totalPaymentAmount');
            this.$orderButton = $('#orderButton');
            this.$notification = $('#orderCompleteNotification');
            this.$notificationMessage = $('#notificationMessage');
            
            // 상태 관리
            this.guestId = '';
            this.orderedProducts = [];
            this.selectedProducts = new Set();
            this.productData = {
                health: [],
                digital: [],
                fancy: [],
                perfume: [],
                haircare: []
            };
            
            // 이벤트 핸들러 바인딩
            this.bindEvents();
            
            // 상품 데이터 로드
            this.loadProductData();
        }
        
        // 이벤트 핸들러 바인딩
        bindEvents() {
            // 비회원 주문 버튼 클릭
            $('#nonMemberOrderBtn').on('click', () => this.openModal());
            
            // 모달 닫기 버튼 클릭
            $('.close-modal').on('click', () => this.closeModal());
            
            // 모달 오버레이 클릭 시 닫기
            this.$modalOverlay.on('click', () => this.closeModal());
            
            // 탭 클릭 이벤트
            $('.tab-item').on('click', (e) => this.switchCategory($(e.currentTarget)));
            
            // 주문 영역 드롭 이벤트
            this.$orderArea.on('dragover', (e) => this.handleDragOver(e));
            this.$orderArea.on('drop', (e) => this.handleDrop(e));
            this.$orderArea.on('dragleave', (e) => this.handleDragLeave(e));
            
            // 문서 전체에 dragend 이벤트 추가
            $(document).on('dragend', '.product-card', (e) => this.handleDragEnd(e));
            
            // 수량 변경 이벤트 (이벤트 위임 사용)
            this.$orderProductList.on('click', '.decrease-quantity', (e) => this.decreaseQuantity($(e.currentTarget).closest('.order-item')));
            this.$orderProductList.on('click', '.increase-quantity', (e) => this.increaseQuantity($(e.currentTarget).closest('.order-item')));
            this.$orderProductList.on('change', '.quantity-input', (e) => this.updateQuantity($(e.currentTarget).closest('.order-item')));
            
            // 주문하기 버튼 클릭
            this.$orderButton.on('click', () => this.placeOrder());
        }
        
        // JSON 데이터 로드
        loadProductData() {
            $.getJSON('./resources/js/data.json', (data) => {
                // 카테고리별로 상품 분류
                data.data.forEach(product => {
                    if (this.productData.hasOwnProperty(product.category)) {
                        this.productData[product.category].push(this.transformProductData(product));
                    }
                });
                
                // 각 카테고리의 상품을 화면에 렌더링
                for (const category in this.productData) {
                    this.renderProductsByCategory(category);
                }
                
                // 첫 번째 카테고리 활성화
                $('.tab-item:first').addClass('active');
                $(`#display-${Object.keys(this.productData)[0]}`).addClass('active');
            }).fail((jqxhr, textStatus, error) => {
                console.error("데이터 로드 실패:", textStatus, error);
                alert("상품 데이터를 불러오는데 실패했습니다. 페이지를 새로고침해주세요.");
            });
        }
        
        // 상품 데이터 변환 (서버 형식 -> 내부 형식)
        transformProductData(serverProduct) {
            // 할인이 적용된 가격 계산
            let finalPrice = serverProduct.price;
            let oldPrice = '';
            
            if (serverProduct.discountOption === 'minus') {
                oldPrice = this.formatCurrency(serverProduct.price);
                finalPrice = serverProduct.price - serverProduct.discountValue;
            } else if (serverProduct.discountOption === 'percent') {
                oldPrice = this.formatCurrency(serverProduct.price);
                finalPrice = Math.round(serverProduct.price * (1 - serverProduct.discountValue));
            }
            
            return {
                id: serverProduct.id.toString(),
                name: serverProduct.title,
                description: serverProduct.description,
                oldPrice: oldPrice,
                price: this.formatCurrency(finalPrice),
                rawPrice: finalPrice,
                shipPrice: serverProduct.shipPrice,
                image: serverProduct.image,
                benefits: serverProduct.benefits,
                category: serverProduct.category
            };
        }
        
        // 모달 열기
        openModal() {
            this.$modal.fadeIn(300);
            this.generateGuestId();
            $('body').css('overflow', 'hidden'); // 스크롤 방지
        }
        
        // 모달 닫기
        closeModal() {
            this.$modal.fadeOut(300);
            $('body').css('overflow', '');
            
            // 상태 초기화
            this.clearOrder();
        }
        
        // 게스트 ID 생성
        generateGuestId() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let id = '';
            for (let i = 0; i < 6; i++) {
                id += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            this.guestId = id;
            this.$guestId.text(id);
        }
        
        // 카테고리별 상품 렌더링
        renderProductsByCategory(category) {
            const $category = $(`#display-${category}`);
            $category.empty();
            
            if (!this.productData[category] || this.productData[category].length === 0) {
                $category.append('<p class="empty-category">이 카테고리에 상품이 없습니다.</p>');
                return;
            }
            
            this.productData[category].forEach(product => {
                // 상품 카드 생성
                const $productCard = $(document.importNode(document.getElementById('productTemplate').content, true).firstElementChild);
                
                // 상품 데이터 설정
                $productCard.attr('data-product-id', product.id);
                $productCard.find('.product-thumb img').attr('src', `./resources/${product.image}`).attr('alt', product.name);
                $productCard.find('.product-title').text(product.name);
                $productCard.find('.current-price').text(product.price);
                
                if (product.oldPrice) {
                    $productCard.find('.old-price').text(product.oldPrice);
                    
                    // 할인율 계산 및 표시
                    const rawPrice = this.parseCurrency(product.oldPrice);
                    const discountPrice = this.parseCurrency(product.price);
                    const discountRate = Math.round((1 - discountPrice / rawPrice) * 100);
                    
                    const $discountBadge = $('<div class="discount-badge"></div>');
                    $discountBadge.text(`-${discountRate}%`);
                    $productCard.find('.product-info').append($discountBadge);
                } else {
                    $productCard.find('.old-price').remove();
                }
                
                // 이벤트 리스너 추가
                this.addDragListeners($productCard);
                
                // 이미 선택된 상품이면 스타일 적용
                if (this.selectedProducts.has(product.id)) {
                    $productCard.addClass('selected');
                }
                
                // 카테고리에 추가
                $category.append($productCard);
            });
        }
        
        // 드래그 리스너 추가
        addDragListeners($card) {
            $card.on('dragstart', (e) => {
                const productId = $card.data('product-id');
                e.originalEvent.dataTransfer.setData('text/plain', productId);
                $card.addClass('dragging');
            });
        }
        
        // 카테고리 전환
        switchCategory($tab) {
            // 모든 탭에서 active 클래스 제거
            $('.tab-item').removeClass('active');
            // 클릭된 탭에 active 클래스 추가
            $tab.addClass('active');
            
            // 모든 카테고리 숨기기
            $('.product-category').removeClass('active');
            // 선택된 카테고리 표시
            const category = $tab.data('category');
            $(`#display-${category}`).addClass('active');
        }
        
        // Drag & Drop 관련 메서드
        handleDragOver(e) {
            e.preventDefault();
            this.$orderArea.addClass('drag-over');
        }
        
        handleDragLeave(e) {
            e.preventDefault();
            this.$orderArea.removeClass('drag-over');
        }
        
        handleDrop(e) {
            e.preventDefault();
            this.$orderArea.removeClass('drag-over');
            
            const productId = e.originalEvent.dataTransfer.getData('text/plain');
            
            // 유효한 상품 ID인지 확인
            if (productId) {
                // 주문 영역 안에 드롭된 경우
                if ($(e.target).closest('#orderArea').length > 0) {
                    this.addToOrder(productId);
                }
            }
        }
        
        handleDragEnd(e) {
            const $card = $(e.target);
            $card.removeClass('dragging');
            
            // 주문 영역 밖으로 드래그된 경우 (주문 취소)
            const productId = $card.data('product-id');
            
            // 이미 주문된 상품이고, 주문 영역 밖으로 드래그된 경우
            if (this.selectedProducts.has(productId) && !$(e.target).closest('#orderArea').length) {
                const orderItemIndex = this.orderedProducts.findIndex(p => p.id === productId);
                
                if (orderItemIndex !== -1) {
                    // 주문에서 제거
                    this.removeFromOrder(productId);
                }
            }
        }
        
        // 상품 찾기
        findProductById(productId) {
            for (const category in this.productData) {
                const found = this.productData[category].find(p => p.id === productId);
                if (found) return found;
            }
            return null;
        }
        
        // 주문에 상품 추가
        addToOrder(productId) {
            // 이미 선택된 상품이면 무시
            if (this.selectedProducts.has(productId)) return;
            
            // 상품 찾기
            const product = this.findProductById(productId);
            if (!product) return;
            
            // 주문 상품 정보 생성
            const orderProduct = {
                ...product,
                quantity: 1,
                totalPrice: product.rawPrice // 초기 총 가격은 상품 가격
            };
            
            // 주문 상품 목록에 추가
            this.orderedProducts.push(orderProduct);
            this.selectedProducts.add(productId);
            
            // 주문 상품 UI 업데이트
            this.renderOrderItem(orderProduct);
            
            // 선택된 상품 스타일 업데이트
            $(`.product-card[data-product-id="${productId}"]`).addClass('selected');
            
            // 주문 영역 상태 업데이트
            this.updateOrderAreaStatus();
            
            // 총액 업데이트
            this.updateTotalAmount();
        }
        
        // 주문에서 상품 제거
        removeFromOrder(productId) {
            // 주문 목록에서 제거
            this.orderedProducts = this.orderedProducts.filter(p => p.id !== productId);
            this.selectedProducts.delete(productId);
            
            // UI에서 주문 항목 제거
            $(`.order-item[data-product-id="${productId}"]`).fadeOut(300, function() {
                $(this).remove();
            });
            
            // 선택된 상품 스타일 업데이트
            $(`.product-card[data-product-id="${productId}"]`).removeClass('selected');
            
            // 주문 영역 상태 업데이트
            this.updateOrderAreaStatus();
            
            // 총액 업데이트
            this.updateTotalAmount();
        }
        
        // 주문 항목 렌더링
        renderOrderItem(product) {
            // 주문 항목 템플릿 복제
            const $orderItem = $(document.importNode(document.getElementById('orderItemTemplate').content, true).firstElementChild);
            
            // 상품 데이터 설정
            $orderItem.attr('data-product-id', product.id);
            $orderItem.find('.order-item-image img').attr('src', `./resources/${product.image}`).attr('alt', product.name);
            $orderItem.find('.order-item-title').text(product.name);
            $orderItem.find('.price').text(product.price);
            $orderItem.find('.quantity-input').val(product.quantity);
            $orderItem.find('.total-price').text(this.formatCurrency(product.totalPrice));
            
            // 드래그 가능하게 설정
            $orderItem.attr('draggable', true);
            $orderItem.on('dragstart', (e) => {
                e.originalEvent.dataTransfer.setData('text/plain', product.id);
                $orderItem.addClass('dragging');
            });
            
            $orderItem.on('dragend', () => {
                $orderItem.removeClass('dragging');
            });
            
            // 주문 목록에 추가
            this.$orderProductList.append($orderItem);
        }
        
        // 주문 영역 상태 업데이트
        updateOrderAreaStatus() {
            if (this.orderedProducts.length === 0) {
                this.$emptyCartMessage.show();
                this.$removeInstruction.hide();
                this.$orderButton.prop('disabled', true);
            } else {
                this.$emptyCartMessage.hide();
                this.$removeInstruction.show();
                this.$orderButton.prop('disabled', false);
            }
        }
        
        // 수량 감소
        decreaseQuantity($orderItem) {
            const productId = $orderItem.data('product-id');
            const $input = $orderItem.find('.quantity-input');
            let quantity = parseInt($input.val(), 10);
            
            if (quantity > 1) {
                quantity--;
                $input.val(quantity);
                this.updateOrderItemQuantity(productId, quantity);
            }
        }
        
        // 수량 증가
        increaseQuantity($orderItem) {
            const productId = $orderItem.data('product-id');
            const $input = $orderItem.find('.quantity-input');
            let quantity = parseInt($input.val(), 10);
            
            quantity++;
            $input.val(quantity);
            this.updateOrderItemQuantity(productId, quantity);
        }
        
        // 수량 직접 변경
        updateQuantity($orderItem) {
            const productId = $orderItem.data('product-id');
            const $input = $orderItem.find('.quantity-input');
            let quantity = parseInt($input.val(), 10);
            
            // 유효성 검사
            if (isNaN(quantity) || quantity < 1) {
                quantity = 1;
                $input.val(quantity);
            }
            
            this.updateOrderItemQuantity(productId, quantity);
        }
        
        // 주문 항목 수량 업데이트
        updateOrderItemQuantity(productId, quantity) {
            // 주문 상품 데이터 업데이트
            const orderProduct = this.orderedProducts.find(p => p.id === productId);
            if (orderProduct) {
                orderProduct.quantity = quantity;
                orderProduct.totalPrice = orderProduct.rawPrice * quantity;
                
                // UI 업데이트
                const $orderItem = $(`.order-item[data-product-id="${productId}"]`);
                $orderItem.find('.total-price').text(this.formatCurrency(orderProduct.totalPrice));
                
                // 총액 업데이트
                this.updateTotalAmount();
            }
        }
        
        // 총액 업데이트
        updateTotalAmount() {
            // 상품 총액 계산
            const totalProductAmount = this.orderedProducts.reduce((sum, product) => sum + product.totalPrice, 0);
            
            // 배송비 설정 (총액 3만원 이상 구매 시 무료, 그렇지 않으면 최대 배송비 적용)
            let shippingFee = 0;
            if (totalProductAmount < 30000 && this.orderedProducts.length > 0) {
                // 주문된 상품 중 가장 높은 배송비 적용
                shippingFee = Math.max(...this.orderedProducts.map(p => p.shipPrice));
            }
            
            // 최종 결제 금액
            const totalPaymentAmount = totalProductAmount + shippingFee;
            
            // UI 업데이트
            this.$totalProductAmount.text(this.formatCurrency(totalProductAmount));
            this.$shippingFee.text(this.formatCurrency(shippingFee));
            this.$totalPaymentAmount.text(this.formatCurrency(totalPaymentAmount));
        }
        
        // 주문 처리
        placeOrder() {
            if (this.orderedProducts.length === 0) return;
            
            // 총 결제 금액 계산
            const totalAmount = this.parseCurrency(this.$totalPaymentAmount.text());
            
            // 주문 완료 메시지 설정
            const message = `방금 비회원 ${this.guestId}님이 ${this.formatCurrency(totalAmount)}원을 결제하셨습니다!`;
            this.$notificationMessage.text(message);
            
            // 모달 닫기
            this.closeModal();
            
            // 알림 표시
            this.$notification.fadeIn(300);
            
            // 3초 후 알림 숨기기
            setTimeout(() => {
                this.$notification.fadeOut(300);
            }, 3000);
        }
        
        // 주문 초기화
        clearOrder() {
            this.orderedProducts = [];
            this.selectedProducts.clear();
            this.$orderProductList.empty();
            this.updateOrderAreaStatus();
            this.updateTotalAmount();
            
            // 모든 선택된 상품 표시 제거
            $('.product-card').removeClass('selected');
        }
        
        // 가격 문자열을 숫자로 변환
        parseCurrency(price) {
            return parseInt(price.replace(/[^\d]/g, ''), 10);
        }
        
        // 숫자를 가격 형식으로 변환
        formatCurrency(amount) {
            return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') + '원';
        }
    }
    
    // 비회원 주문 시스템 인스턴스 생성
    const nonMemberOrderSystem = new NonMemberOrderSystem();
});