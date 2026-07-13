<?php

class Elementor_Wallet_Checker_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'wallet_checker_widget';
    }
    
    public function get_title() {
        return __('Wallet Checker', 'wallet-checker');
    }
    
    public function get_icon() {
        return 'eicon-form-horizontal';
    }
    
    public function get_categories() {
        return ['forms'];
    }
    
    protected function register_controls() {
        // Title Section
        $this->start_controls_section(
            'title_section',
            [
                'label' => __('Title', 'wallet-checker'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'title_text',
            [
                'label' => __('Title', 'wallet-checker'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Check Wallet Eligibility', 'wallet-checker'),
                'placeholder' => __('Enter title', 'wallet-checker'),
            ]
        );
        
        $this->add_control(
            'show_title',
            [
                'label' => __('Show Title', 'wallet-checker'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'wallet-checker'),
                'label_off' => __('No', 'wallet-checker'),
                'default' => 'yes',
            ]
        );
        
        $this->end_controls_section();
        
        // Button Section
        $this->start_controls_section(
            'button_section',
            [
                'label' => __('Button', 'wallet-checker'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'button_text',
            [
                'label' => __('Button Text', 'wallet-checker'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Check Eligibility', 'wallet-checker'),
            ]
        );
        
        $this->end_controls_section();
        
        // Container Styling
        $this->start_controls_section(
            'container_style',
            [
                'label' => __('Container', 'wallet-checker'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'container_background',
                'label' => __('Background', 'wallet-checker'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .wallet-checker-container',
                'default' => [
                    'background' => 'classic',
                    'color' => '#f5f7fa',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'container_padding',
            [
                'label' => __('Padding', 'wallet-checker'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => '25',
                    'right' => '25',
                    'bottom' => '25',
                    'left' => '25',
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wallet-checker-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'container_border_radius',
            [
                'label' => __('Border Radius', 'wallet-checker'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '12',
                    'right' => '12',
                    'bottom' => '12',
                    'left' => '12',
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wallet-checker-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_box_shadow',
                'label' => __('Box Shadow', 'wallet-checker'),
                'selector' => '{{WRAPPER}} .wallet-checker-container',
            ]
        );
        
        $this->end_controls_section();
        
        // Title Styling
        $this->start_controls_section(
            'title_style',
            [
                'label' => __('Title Style', 'wallet-checker'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __('Typography', 'wallet-checker'),
                'selector' => '{{WRAPPER}} .wallet-checker-container h2',
            ]
        );
        
        $this->add_control(
            'title_color',
            [
                'label' => __('Color', 'wallet-checker'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .wallet-checker-container h2' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'title_alignment',
            [
                'label' => __('Alignment', 'wallet-checker'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'wallet-checker'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'wallet-checker'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'wallet-checker'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .wallet-checker-container h2' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Input Styling
        $this->start_controls_section(
            'input_style',
            [
                'label' => __('Input Field', 'wallet-checker'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'input_typography',
                'label' => __('Typography', 'wallet-checker'),
                'selector' => '{{WRAPPER}} .wallet-checker-form input',
            ]
        );
        
        $this->add_control(
            'input_text_color',
            [
                'label' => __('Text Color', 'wallet-checker'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333',
                'selectors' => [
                    '{{WRAPPER}} .wallet-checker-form input' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'input_background_color',
            [
                'label' => __('Background Color', 'wallet-checker'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .wallet-checker-form input' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'input_border_color',
            [
                'label' => __('Border Color', 'wallet-checker'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ddd',
                'selectors' => [
                    '{{WRAPPER}} .wallet-checker-form input' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Button Styling
        $this->start_controls_section(
            'button_style',
            [
                'label' => __('Button Style', 'wallet-checker'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'label' => __('Typography', 'wallet-checker'),
                'selector' => '{{WRAPPER}} .btn-check',
            ]
        );
        
        $this->add_control(
            'button_text_color',
            [
                'label' => __('Text Color', 'wallet-checker'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .btn-check' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'button_background',
                'label' => __('Background', 'wallet-checker'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .btn-check',
            ]
        );
        
        $this->add_control(
            'button_border_radius',
            [
                'label' => __('Border Radius', 'wallet-checker'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '6',
                    'right' => '6',
                    'bottom' => '6',
                    'left' => '6',
                    'unit' => 'px',
                    'isLinked' => true,
                ],
                'selectors' => [
                    '{{WRAPPER}} .btn-check' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Result Styling
        $this->start_controls_section(
            'result_style',
            [
                'label' => __('Result Styling', 'wallet-checker'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'eligible_bg_color',
            [
                'label' => __('Eligible Background', 'wallet-checker'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#d4edda',
                'selectors' => [
                    '{{WRAPPER}} .result-eligible' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'not_eligible_bg_color',
            [
                'label' => __('Not Eligible Background', 'wallet-checker'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f8d7da',
                'selectors' => [
                    '{{WRAPPER}} .result-not-eligible' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        ?>
        <div class="wallet-checker-container elementor-widget-wallet-checker">
            <?php if ($settings['show_title'] === 'yes'): ?>
                <h2><?php echo esc_html($settings['title_text']); ?></h2>
            <?php endif; ?>
            
            <div class="wallet-checker-form">
                <input type="text" class="wallet-address-elementor" placeholder="Enter Ethereum wallet address (0x...)" />
                <button class="btn-check btn-check-elementor"><?php echo esc_html($settings['button_text']); ?></button>
            </div>
            
            <div class="wallet-result" style="display:none;">
                <div class="result-eligible" style="display:none;">
                    <span class="badge-eligible">✓ ELIGIBLE</span>
                    <div class="result-content">
                        <p><strong>Address:</strong> <span class="result-address"></span></p>
                    </div>
                </div>
                <div class="result-not-eligible" style="display:none;">
                    <span class="badge-not-eligible">✗ NOT ELIGIBLE</span>
                    <div class="result-content">
                        <p><strong>Address:</strong> <span class="result-address-not"></span></p>
                    </div>
                </div>
            </div>
            
            <div class="wallet-error" style="display:none;"></div>
        </div>
        <?php
    }
}
