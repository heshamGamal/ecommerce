'use client';

import Link from 'next/link';
import { Product } from '@/types';
import { useCart } from '@/context/CartContext';
import ImageWithFallback from '@/components/media/ImageWithFallback';

interface ProductCardProps {
  product: Product;
  }

  export default function ProductCard({ product }: ProductCardProps) {
    const { addToCart } = useCart();

      return (
          <div className="bg-white border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition flex flex-col justify-between">
                <Link href={`/products/${product.slug || product.id}`}>
                        <div className="relative w-full h-48 bg-gray-100">
                                  <ImageWithFallback
                                              src={product.main_image}
                                                          alt={product.name}
                                                                      className="w-full h-full object-cover"
                                                                                />
                                                                                        </div>
                                                                                                <div className="p-4">
                                                                                                          <h3 className="font-bold text-gray-800 text-lg mb-2 line-clamp-1">
                                                                                                                      {product.name}
                                                                                                                                </h3>
                                                                                                                                          <p className="text-gray-500 text-sm mb-3 line-clamp-2">
                                                                                                                                                      {product.short_description || product.description}
                                                                                                                                                                </p>
                                                                                                                                                                          <div className="flex items-center justify-between">
                                                                                                                                                                                      <span className="text-blue-600 font-extrabold text-lg">
                                                                                                                                                                                                    {product.price} EGP
                                                                                                                                                                                                                </span>
                                                                                                                                                                                                                            {product.rating_avg && (
                                                                                                                                                                                                                                          <span className="text-yellow-500 text-sm font-semibold">
                                                                                                                                                                                                                                                          ★ {product.rating_avg}
                                                                                                                                                                                                                                                                        </span>
                                                                                                                                                                                                                                                                                    )}
                                                                                                                                                                                                                                                                                              </div>
                                                                                                                                                                                                                                                                                                      </div>
                                                                                                                                                                                                                                                                                                            </Link>
                                                                                                                                                                                                                                                                                                                  <div className="p-4 pt-0">
                                                                                                                                                                                                                                                                                                                          <button
                                                                                                                                                                                                                                                                                                                                    onClick={() => addToCart(product)}
                                                                                                                                                                                                                                                                                                                                              className="w-full bg-blue-600 text-white text-sm py-2 px-4 rounded-md font-semibold hover:bg-blue-700 transition"
                                                                                                                                                                                                                                                                                                                                                      >
                                                                                                                                                                                                                                                                                                                                                                أضف إلى السلة
                                                                                                                                                                                                                                                                                                                                                                        </button>
                                                                                                                                                                                                                                                                                                                                                                              </div>
                                                                                                                                                                                                                                                                                                                                                                                  </div>
                                                                                                                                                                                                                                                                                                                                                                                    );
                                                                                                                                                                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                                                                                                                                                        