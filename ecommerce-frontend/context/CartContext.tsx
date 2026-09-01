'use client';

import React, { createContext, useContext, useState, useEffect } from 'react';
import { Product, CartItem } from '@/types';

interface CartContextType {
  cart: CartItem[];
    addToCart: (product: Product, quantity?: number) => void;
      removeFromCart: (productId: number) => void;
        clearCart: () => void;
          totalAmount: number;
          }

          const CartContext = createContext<CartContextType | undefined>(undefined);

          export function CartProvider({ children }: { children: React.ReactNode }) {
            const [cart, setCart] = useState<CartItem[]>([]);

              useEffect(() => {
                  const savedCart = localStorage.getItem('cart');
                      if (savedCart) {
                            try {
                                    setCart(JSON.parse(savedCart));
                                          } catch (e) {
                                                  console.error('Failed to parse cart', e);
                                                        }
                                                            }
                                                              }, []);

                                                                useEffect(() => {
                                                                    localStorage.setItem('cart', JSON.stringify(cart));
                                                                      }, [cart]);

                                                                        const addToCart = (product: Product, quantity = 1) => {
                                                                            setCart((prev) => {
                                                                                  const existingIndex = prev.findIndex((item) => item.product.id === product.id);
                                                                                        if (existingIndex > -1) {
                                                                                                const updated = [...prev];
                                                                                                        updated[existingIndex].quantity += quantity;
                                                                                                                return updated;
                                                                                                                      }
                                                                                                                            return [...prev, { product, quantity }];
                                                                                                                                });
                                                                                                                                  };

                                                                                                                                    const removeFromCart = (productId: number) => {
                                                                                                                                        setCart((prev) => prev.filter((item) => item.product.id !== productId));
                                                                                                                                          };

                                                                                                                                            const clearCart = () => setCart([]);

                                                                                                                                              const totalAmount = cart.reduce(
                                                                                                                                                  (sum, item) => sum + item.product.price * item.quantity,
                                                                                                                                                      0
                                                                                                                                                        );

                                                                                                                                                          return (
                                                                                                                                                              <CartContext.Provider
                                                                                                                                                                    value={{ cart, addToCart, removeFromCart, clearCart, totalAmount }}
                                                                                                                                                                        >
                                                                                                                                                                              {children}
                                                                                                                                                                                  </CartContext.Provider>
                                                                                                                                                                                    );
                                                                                                                                                                                    }

                                                                                                                                                                                    export const useCart = () => {
                                                                                                                                                                                      const context = useContext(CartContext);
                                                                                                                                                                                        if (!context) throw new Error('useCart must be used within a CartProvider');
                                                                                                                                                                                          return context;
                                                                                                                                                                                          };
                                                                                                                                                                                          