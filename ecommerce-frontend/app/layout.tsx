import type { Metadata } from 'next';
import './globals.css';
import { CartProvider } from '@/context/CartContext';
import Header from '@/components/layout/Header';
import Footer from '@/components/layout/Footer';

export const metadata: Metadata = {
  title: 'متجرنا الإلكتروني',
    description: 'متجر متكامل يدعم الشراء المباشر والبحث الذكي',
    };

    export default function RootLayout({
      children,
      }: {
        children: React.ReactNode;
        }) {
          return (
              <html lang="ar" dir="rtl">
                    <body className="bg-gray-50 text-gray-900 flex flex-col min-h-screen">
                            <CartProvider>
                                      <Header />
                                                <div className="flex-grow">{children}</div>
                                                          <Footer />
                                                                  </CartProvider>
                                                                        </body>
                                                                            </html>
                                                                              );
                                                                              }
                                                                              